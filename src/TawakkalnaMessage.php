<?php

namespace Abather\TawakkalnaMessage;

class TawakkalnaMessage
{
    protected $message;
    protected $phone;

    public function __construct(string $message)
    {
        $this->message($message);
    }

    public function message(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function phone(string $phone):static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public static function make(...$attributes): static
    {
        return new static(...$attributes);
    }
}
