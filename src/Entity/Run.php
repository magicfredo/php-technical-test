<?php


namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups as SerializerGroups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RunRepository")
 */
class Run
{
    public const TYPE_TRAINING = 'TRAINING';
    public const TYPE_RUNNING = 'RUNNING';
    public const TYPE_LEISURE = 'LEISURE';

    public const TYPES = [
        self::TYPE_TRAINING,
        self::TYPE_RUNNING,
        self::TYPE_LEISURE,
    ];

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @SerializerGroups({"run"})
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="user_id")
     *
     * @var int
     */
    private $userId;

    /**
     * @ORM\Column(
     *     type="string",
     *     name="type",
     *     nullable=false,
     *     length=20
     * )
     * mySql: columnDefinition="ENUM('TRAINING', 'RUNNING', 'LEISURE')"
     *
     * @Assert\NotBlank(message="error.type.not_blank")
     * @Assert\Choice(choices={"TRAINING", "RUNNING", "LEISURE"}, message="error.type.choice")
     *
     * @SerializerGroups({"run"})
     *
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", name="started_at")
     *
     * @SerializerGroups({"run"})
     *
     * @var DateTime
     */
    private $startedAt;

    /**
     * @ORM\Column(type="integer")
     *
     * @SerializerGroups({"run"})
     *
     * @var int
     */
    private $duration;

    /**
     * @SerializerGroups({"run"})
     * 
     * @var string
     */
    private $durationFormatted;

    /**
     * @ORM\Column(type="integer")
     *
     * @SerializerGroups({"run"})
     *
     * @var int
     */
    private $distance;

    /**
     * @ORM\Column(type="text")
     *
     * @SerializerGroups({"run"})
     *
     * @var string
     */
    private $comment;

    /**
     * @ORM\Column(type="float", name="average_speed")
     *
     * @SerializerGroups({"run"})
     *
     * @var string
     */
    private $averageSpeed;

    /**
     * @ORM\Column(type="float", name="average_pace")
     *
     * @SerializerGroups({"run"})
     *
     * @var string
     */
    private $averagePace;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="runsList")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @SerializerGroups({"user"})
     *
     * @var User
     */
    private $user;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Run
     */
    public function setId(int $id): Run
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Run
     */
    public function setUserId(int $userId = null): Run
    {
        $this->userId = $userId;
        $this->user = null;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Run
     */
    public function setType(string $type): Run
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getStartedAt(): ?DateTime
    {
        if (null === $this->startedAt) {
            $this->startedAt = new DateTime();
        }

        return $this->startedAt;
    }

    /**
     * @param DateTime $startedAt
     * @return Run
     */
    public function setStartedAt(DateTime $startedAt): Run
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Run
     */
    public function setDuration(int $duration): Run
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDurationFormatted(): ?string
    {
        return $this->durationFormatted;
    }

    /**
     * @param string $durationFormatted
     * @return Run
     */
    public function setDurationFormatted(string $durationFormatted): Run
    {
        $this->durationFormatted = $durationFormatted;
        return $this;
    }

    /**
     * @return int
     */
    public function getDistance(): ?int
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     * @return Run
     */
    public function setDistance(int $distance): Run
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Run
     */
    public function setComment(string $comment): Run
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getAverageSpeed(): ?string
    {
        return $this->averageSpeed;
    }

    /**
     * @param string $averageSpeed
     * @return Run
     */
    public function setAverageSpeed(string $averageSpeed): Run
    {
        $this->averageSpeed = $averageSpeed;
        return $this;
    }

    /**
     * @return string
     */
    public function getAveragePace(): ?string
    {
        return $this->averagePace;
    }

    /**
     * @param string $averagePace
     * @return Run
     */
    public function setAveragePace(string $averagePace): Run
    {
        $this->averagePace = $averagePace;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Run
     */
    public function setUser(User $user = null): Run
    {
        $this->user = $user;
        $this->userId = null !== $user && $user->getId() ? $user->getId() : null;

        return $this;
    }
}