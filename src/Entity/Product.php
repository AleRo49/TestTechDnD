<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use Exception;
use JsonSerializable;

class Product implements JsonSerializable
{
    /**
     * @var int
     */
    private int $sku;
    /**
     * @var DateTime
     */
    private DateTime $createdAt;
    /**
     * @var string
     */
    private string $description;
    /**
     * @var float
     */
    private float $price;
    /**
     * @var string
     */
    private string $title;
    private string $slug;
    private bool $isEnabled;
    private string $currency;

    private const ENABLED = 'Enable';
    private const DISABLED = 'Disable';

    /**
     * @param int $sku
     * @return void
     */
    public function setSku(int $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @param DateTime $createdAt
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Sanitize description
     * @return string
     */
    private function sanitizeDescription(): string
    {
        $desc = preg_replace("/\<(\s*)?br(\s*)?\/?\>/i", "\n", $this->getDescription());
        return str_replace("\\r", "\n", $desc);
    }

    /**
     * Round price to 0.1 with precision 2 and ","
     * @return string
     */
    private function getRoundedPriceWithCurrency(): string
    {
        $price = number_format(round($this->price, 1), 2, ',', '');
        return $price . $this->currency;
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    private function getSlugFromTitle(): string
    {
        $slug = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->title);
        return str_replace(" ", "-", strtolower($slug));
    }
    /**
     * @param bool $isEnabled
     * @return void
     */
    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @param array $data
     * @return self
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        $product = new self();
        $product->setSku($data['sku']);
        $product->setCreatedAt(new DateTime($data['created_at'], new DateTimeZone('CET')));
        $product->setIsEnabled((bool)$data['is_enabled']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);
        $product->setCurrency($data['currency']);
        $product->setTitle($data['title']);
        $product->setSlug($product->getSlugFromTitle());
        return $product;
    }

    public function getFormattedArray(): array
    {
        return [
            $this->sku,
            $this->title,
            $this->isEnabled ? self::ENABLED : self::DISABLED,
            $this->getRoundedPriceWithCurrency(),
            $this->currency,
            $this->sanitizeDescription(),
            $this->createdAt->format('l, d-M-Y H:i:s e'),
            $this->slug
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'product' => [
                'sku' => $this->sku,
                'title' => $this->title,
                'description' => $this->description,
                'price' => $this->getRoundedPriceWithCurrency(),
                'currency' => $this->currency,
                'isEnabled' => $this->isEnabled ? self::ENABLED : self::DISABLED,
                'createdAt' => $this->createdAt->format('l, d-M-Y H:i:s e'),
                'slug' => $this->slug,
            ]
        ];
    }
}