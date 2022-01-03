<?php

namespace twent\mvccore\form;

use twent\mvccore\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;

    abstract public function renderInput(): string;

    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf('
            <div class="row mb-3 form-group">
                <label class="col-sm-2 col-form-label">%s</label>
                <div class="col-sm-10">
                    %s
                </div>
                <div class="invalid-tooltip">
                    %s
                </div>
            </div>
        ',
            $this->model->getLabel($this->attribute),
            $this->renderinput(),
            $this->model->getFirstError($this->attribute)
        );


    }

}
