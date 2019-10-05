<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionalEmail extends Model
{
	const EMAIL_PENDING = 0;
	const EMAIL_SENT = 1;
	const EMAIL_FAILED = 2;
    protected $fillable = ['subject','content', 'status'];

    public function saveEmailStatus($request) {
        
        $this->status = self::EMAIL_PENDING;

        $this->subject = $request->subject;

        $this->content = $request->content;

        if ($this->save()) {
            return $this->id;
        }
        return false;
    }
}
