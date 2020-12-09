<?php declare(strict_types=1);

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
final class BrandingImageData implements Entity
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="storageKey", type="string")
     */
    private $storageKey;

    /**
     * @var string
     * @JUSTIMMO\Column(path="position", type="string")
     */
    private $position;

    /**
     * @var string
     * @JUSTIMMO\Column(path="fit", type="string")
     */
    private $fit;

    /**
     * @return string
     */
    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getFit(): string
    {
        return $this->fit;
    }
}
