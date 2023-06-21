<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\HasApiTokens;
use TheSeer\Tokenizer\Token;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function passwordVerify(string $password)
    {
        return password_verify($password, $this->password);
    }

    public static function fromToken(string $token): ?User
    {
        $token = UserToken::find($token);
        if (empty($token)) {
            return null;
        }
        return User::find($token->uid);
    }

    public function isAdmin(): bool
    {
        return ($this->role === "admin");
    }

    /**
     * @return int
     * Renvoie le nombre de minutes depuis la dernière action enregistrée sur le site.
     * On considère un utilisateur en ligne si la valeur retournée est inférieure à 5.
     */
    public function lastSeen(): int
    {
        $token = UserToken::where("uid", $this->id)
            ->orderBy("lastSeen", "DESC")
            ->limit(1)
            ->get();

        if (count($token) === 0) {
            return -1;
        }

        $token = $token[0];
        return (Carbon::now()->diffInMinutes($token->lastSeen));
    }

    public function lastSeenBadge(): string
    {
        $ls = $this->lastSeen();

        switch (true) {
            case $ls === -1:
                $color = "gray";
                $text = "Jamais";
                break;
            case $ls <= 5:
                $color = "green";
                $text = "En ligne";
                break;
            case $ls <= 59:
                $color = "yellow";
                $text = "Il y a $ls minute(s)";
                break;
            case $ls <= 1439:
                $color = "yellow";
                $text = "Il y a " . intdiv($ls, 60) . " heure(s)";
                break;
            default:
                $color = "yellow";
                $text = "Il y a " . intdiv($ls, 1440) . " jour(s)";
                break;
        }

        return '
        <span class="inline-flex items-center gap-x-1.5 rounded-md bg-' . $color . '-100 px-2 py-1 text-xs font-medium text-' . $color . '-600">
            <svg class="h-1.5 w-1.5 fill-' . $color . '-400" viewBox="0 0 6 6" aria-hidden="true">
                <circle cx="3" cy="3" r="3"/>
            </svg>
            ' . $text . '
        </span>';

    }

    public function getPublicName(): string
    {
        return ucfirst($this->prenom) . " " . ucfirst(mb_substr($this->nom, 0, 1));
    }

    public static function connected(): User
    {
        return self::fromToken(Cookie::get("token"));
    }
}
