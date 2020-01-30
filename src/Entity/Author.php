<?php

namespace App\Entity;

use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

class Author implements JsonSerializable
{
    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the first name."
     * )
     * @var string
     */
    private $firstName;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the last name."
     * )
     * @var string
     */
    private $lastName;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the birthday."
     * )
     * @var string
     */
    private $birthday;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the biography."
     * )
     * @var string
     */
    private $biography;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the gender."
     * )
     * @var string
     */
    private $gender;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="You must enter the place of birth."
     * )
     * @var string
     */
    private $placeOfBirth;

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday(string $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     */
    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    /**
     * @param string $placeOfBirth
     */
    public function setPlaceOfBirth(string $placeOfBirth): void
    {
        $this->placeOfBirth = $placeOfBirth;
    }

    public function jsonSerialize()
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'birthday' => $this->birthday,
            'biography' => $this->biography,
            'gender' => $this->gender,
            'place_of_birth' => $this->placeOfBirth
        ];
    }

}