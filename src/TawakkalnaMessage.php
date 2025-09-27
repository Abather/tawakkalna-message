<?php

namespace Abather\TawakkalnaMessage;

class TawakkalnaMessage
{
    protected $message;

    protected $phone;

    protected $receiver;

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

    public function phone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function receiver(string $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function validate(): bool
    {
        return $this->validateReceiver() && $this->validateMessage();
    }

    public function validateMessage(): bool
    {
        $length = str($this->getMessage())->length();

        if ($length > 4000) {
            throw new \Exception('Message excuse max length 4000 characters');
        }

        return true;
    }

    public function validateReceiver(): bool
    {
        $id = trim($this->receiver);

        if (! $id) {
            $this->throwReceiverError();
        }
        if (! is_numeric($id)) {
            $this->throwReceiverError();
        }
        if (strlen($id) !== 10) {
            $this->throwReceiverError();
        }
        $type = substr($id, 0, 1);
        if ($type != 2 && $type != 1) {
            $this->throwReceiverError();
        }
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            if ($i % 2 == 0) {
                $ZFOdd = str_pad((substr($id, $i, 1) * 2), 2, '0', STR_PAD_LEFT);
                $sum += substr($ZFOdd, 0, 1) + substr($ZFOdd, 1, 1);
            } else {
                $sum += substr($id, $i, 1);
            }
        }

        return $sum % 10 ? $this->throwReceiverError() : true;
    }

    private function throwReceiverError()
    {
        throw new \Exception('National Id invalid');
    }

    public static function make(...$attributes): static
    {
        return new static(...$attributes);
    }
}
