<?php

namespace Sendportal\Base\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Store which fields are boolean in the model
     *
     * Note that any boolean fields not in the fillable
     * array will not be automatically set in the repo
     */
    protected array $booleanFields = [];

    /**
     * Return all boolean fields for the model
     *
     * @return array
     */
    public function getBooleanFields(): array
    {
        return $this->booleanFields;
    }
}
