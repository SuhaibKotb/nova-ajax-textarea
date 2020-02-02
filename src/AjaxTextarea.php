<?php

namespace SuhaibKotb\AjaxTextarea;

use Laravel\Nova\Fields\Field;

class AjaxTextarea extends Field
{
    use Expandable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'ajax-textarea';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * The number of rows used for the textarea.
     *
     * @var int
     */
    public $rows = 5;

    /**
     * Set the number of rows used for the textarea.
     *
     * @param  int $rows
     * @return $this
     */
    public function rows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return string
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        parent::resolveForDisplay($resource, $attribute);

        return $this->value = e($this->value);
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'rows' => $this->rows,
            'shouldShow' => $this->shouldBeExpanded(),
        ]);
    }

    /**
     * Indicates if the element should be shown on the detail view.
     * @var bool
     */
    public $showOnDetail = false;

    /**
     * @param $endpoint
     * @return $this
     */
    public function get($endpoint)
    {
        $this->withMeta(['endpoint' => $endpoint]);

        return $this;
    }

    /**
     * @param $attribute
     * @return $this
     */
    public function parent($attribute)
    {
        $this->withMeta(['parent_attribute' => $attribute]);

        return $this;
    }
}
