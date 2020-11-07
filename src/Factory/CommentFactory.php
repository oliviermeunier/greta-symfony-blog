<?php

namespace App\Factory;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Comment|Proxy findOrCreate(array $attributes)
 * @method static Comment|Proxy random()
 * @method static Comment[]|Proxy[] randomSet(int $number)
 * @method static Comment[]|Proxy[] randomRange(int $min, int $max)
 * @method static CommentRepository|RepositoryProxy repository()
 * @method Comment|Proxy create($attributes = [])
 * @method Comment[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class CommentFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'user' => self::faker()->name(),
            'content' => self::faker()->text(250),
            'createdAt' => self::faker()->dateTimeBetween('-3 years', 'now', 'Europe/Paris'),
            'post' => PostFactory::random()
        ];
    }

    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function(Comment $comment) {
                $postCreatedAt = $comment->getPost()->getCreatedAt();
                $comment->setCreatedAt(self::faker()->dateTimeBetween($postCreatedAt));
            });
    }

    protected static function getClass(): string
    {
        return Comment::class;
    }
}
