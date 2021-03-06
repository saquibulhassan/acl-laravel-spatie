<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AclEnum extends Enum
{
    const Read = 'Read';
    const Create = 'Create';
    const Update = 'Update';
    const Delete = 'Delete';
    const Preview = 'Preview';
    const Download = 'Download';
}
