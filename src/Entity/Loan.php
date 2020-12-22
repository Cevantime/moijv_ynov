<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{

    const STATUS_WAITING = "status.waiting";
    const STATUS_VALIDATED = "status.validated";
    const STATUS_REFUSED = "status.refused";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=LoanMessage::class, mappedBy="loan", orphanRemoval=true)
     */
    private $loanMessages;

    public function __construct()
    {
        $this->loanMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isAccepted()
    {
        return $this->status === self::STATUS_VALIDATED;
    }

    public function isRefused()
    {
        return $this->status === self::STATUS_REFUSED;
    }

    /**
     * @return Collection|LoanMessage[]
     */
    public function getLoanMessages(): Collection
    {
        return $this->loanMessages;
    }

    public function addLoanMessage(LoanMessage $loanMessage): self
    {
        if (!$this->loanMessages->contains($loanMessage)) {
            $this->loanMessages[] = $loanMessage;
            $loanMessage->setLoan($this);
        }

        return $this;
    }

    public function removeLoanMessage(LoanMessage $loanMessage): self
    {
        if ($this->loanMessages->removeElement($loanMessage)) {
            // set the owning side to null (unless already changed)
            if ($loanMessage->getLoan() === $this) {
                $loanMessage->setLoan(null);
            }
        }

        return $this;
    }
}
