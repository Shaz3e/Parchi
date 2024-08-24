<?php

use App\Models\Setting;

function Setting($appSettingName){return Setting::where('name', $appSettingName)->value('setting');}
