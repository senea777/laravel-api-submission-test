<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    public int $id;

    public string $name;

    public string $email;

    public string $message;
    protected $fillable = [ 'id', 'name', 'email', 'message'];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->name = $attributes['name'] ?? '';
        $this->email = $attributes['email'] ?? '';
        $this->message = $attributes['message'] ?? '';
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
