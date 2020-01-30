<?php

namespace App\Entity;

use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

class Book implements JsonSerializable
{
    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the book title."
     * )
     * @var string
     */
    private $title;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must choose the release date."
     * )
     * @var string
     */
    private $releaseDate;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the book description."
     * )
     * @var string
     */
    private $description;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the book isbn."
     * )
     * @var string
     */
    private $isbn;

    /**
     * @var string
     */
    private $format;

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="You must enter the number of pages."
     * )
     * @var int
     */
    private $numberOfPages;

    /**
     * Author ID. Many to one relation
     * @Assert\Type("string")
     * @var string
     */
    private $author;

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    /**
     * @param string $releaseDate
     */
    public function setReleaseDate(string $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * @return int
     */
    public function getNumberOfPages(): ?int
    {
        return $this->numberOfPages;
    }

    /**
     * @param int $numberOfPages
     */
    public function setNumberOfPages(int $numberOfPages): void
    {
        $this->numberOfPages = $numberOfPages;
    }

    /**
     * @return string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function jsonSerialize()
    {
        return [
            'author' => [
                'id' => $this->author
            ],
            'title' => $this->title,
            'release_date' => $this->releaseDate,
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => $this->description,
            'isbn' => '9798388378514',
            'format' => $this->format,
            'number_of_pages' => $this->numberOfPages
        ];
    }
}