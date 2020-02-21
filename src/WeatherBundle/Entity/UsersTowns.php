<?php

namespace WeatherBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsersTowns
 *
 * @ORM\Table(name="users_towns")
 * @ORM\Entity(repositoryClass="WeatherBundle\Repository\UsersTownsRepository")
 */
class UsersTowns
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="town_id", type="integer")
     */
    private $townId;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return UsersTowns
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set townId.
     *
     * @param int $townId
     *
     * @return UsersTowns
     */
    public function setTownId($townId)
    {
        $this->townId = $townId;

        return $this;
    }

    /**
     * Get townId.
     *
     * @return int
     */
    public function getTownId()
    {
        return $this->townId;
    }
}
