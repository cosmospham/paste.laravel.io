<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class Paste extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'pastes';

    public static function fromRequest(Request $request): self
    {
        return static::createNew(new static(), $request);
    }

    public static function fromFork(self $fork, Request $request): self
    {
        $paste = new static();
        $paste->parent_id = $fork->id;

        return static::createNew($paste, $request);
    }

    private static function createNew(self $paste, Request $request): self
    {
        $paste->code = $request->get('code');
        $paste->hash = Uuid::uuid4()->toString();

        $paste->save();

        return $paste;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteKeyName()
    {
        return 'hash';
    }
}
