<?php
namespace Justimmo\Tests;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * 2.0 declares native types on the public surface. A docblock publishes the signature a release
 * early, so an integrator can see what is coming and a static analyser can warn before it is
 * enforced. This test holds that surface at full coverage
 */
class DocblockCoverageTest extends TestCase
{
    /**
     * Methods whose return the SDK cannot honestly document, because the class contradicts the
     * interface it implements. Each one is a 2.0 blocker rather than a missing docblock:
     * JustimmoNullApi::callExpose() returns null where JustimmoApiInterface says string, and the
     * two stub mappers return nothing where MapperInterface says string
     */
    const WITHOUT_A_RETURN_TYPE = [
        'Justimmo\Api\JustimmoNullApi::callExpose',
        'Justimmo\Model\Mapper\V1\BasicDataMapper::getProperty',
        'Justimmo\Model\Mapper\V1\BasicDataMapper::getSetter',
        'Justimmo\Model\Mapper\V1\BasicDataMapper::getType',
        'Justimmo\Model\Mapper\V1\RealtyInquiryMapper::getProperty',
        'Justimmo\Model\Mapper\V1\RealtyInquiryMapper::getSetter',
        'Justimmo\Model\Mapper\V1\RealtyInquiryMapper::getType',
    ];

    /**
     * Every public method declares what it returns, natively or in its docblock
     */
    public function testEveryPublicMethodSaysWhatItReturns(): void
    {
        $this->assertSame(self::WITHOUT_A_RETURN_TYPE, $this->undocumented('return'));
    }

    /**
     * Every untyped parameter of a public method is described
     */
    public function testEveryUntypedParameterIsDescribed(): void
    {
        $this->assertSame(self::WITHOUT_A_RETURN_TYPE, $this->undocumented('param'));
    }

    /**
     * @return array<int, string> the offending methods as Class::method, sorted
     */
    private function undocumented(string $kind): array
    {
        $found = [];

        foreach ($this->sources() as $file) {
            $source  = (string) file_get_contents($file);
            $class   = $this->classOf($source);
            $pattern = '~(/\*\*(?:[^*]|\*(?!/))*\*/\s*)?(?:abstract\s+|final\s+)?'
                . 'public (?:static )?function (\w+)\s*\(([^)]*)\)(\s*:\s*[\w\\\\|?]+)?~';

            preg_match_all($pattern, $source, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $doc  = $match[1] ?? '';
                $name = $match[2];

                if (strpos($name, '__') === 0 || stripos($doc, 'inheritdoc') !== false) {
                    continue;
                }

                if ($this->isDocumented($kind, $doc, $match[3], $match[4] ?? '')) {
                    continue;
                }

                $found[] = $class . '::' . $name;
            }
        }

        sort($found);

        return $found;
    }

    private function isDocumented(string $kind, string $doc, string $args, string $nativeReturn): bool
    {
        if ($kind === 'return') {
            return $nativeReturn !== '' || strpos($doc, '@return') !== false;
        }

        $untyped = 0;

        foreach (explode(',', $args) as $argument) {
            if (trim($argument) !== '' && !preg_match('~^\s*\??[\w\\\\|]+\s+\$~', $argument)) {
                $untyped++;
            }
        }

        return $untyped === 0 || substr_count($doc, '@param') >= $untyped;
    }

    private function classOf(string $source): string
    {
        preg_match('~^namespace\s+([^;]+);~m', $source, $ns);
        preg_match('~^(?:abstract |final )?(?:class|interface|trait)\s+(\w+)~m', $source, $name);

        return trim($ns[1] ?? '') . '\\' . ($name[1] ?? '');
    }

    /**
     * @return array<int, string>
     */
    private function sources(): array
    {
        $files    = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(__DIR__ . '/../../../src', FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}
