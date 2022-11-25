<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    private $oldPassword;

    /**
     * @Assert\Regex(
     *  pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/",
     *  message="Votre mot de passe doit être composé d'au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial et doit faire au moins 8 caractères."
     * )
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(
     *  propertyPath="newPassword",
     *  message="Vous n'avez pas correctement confirmé votre nouveau mot de passe"
     * )
     */
    private $confirmPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
