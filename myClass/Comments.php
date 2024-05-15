<?php
namespace MyClass;

use DateTime;

class Comments
{
	protected int $id;
    protected string $body;
    protected DateTime $createdAt;
    protected int $newsId;

	public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getNewsId(): int
    {
        return $this->newsId;
    }

    public function setNewsId(int $newsId): self
    {
        $this->newsId = $newsId;

        return $this;
    }
}