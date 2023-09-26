<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsagePolicy;
use App\Models\PrivacyPolicy;
use App\Http\Resources\Api\PolicyResource;
use App\Models\About;

class UsagePolicyController extends Controller
{
    public function getUsagePolicy() {
          return PolicyResource::collection(UsagePolicy::all());
    }

    public function getPrivacyPolicy() {
        return PolicyResource::collection(PrivacyPolicy::all());
  }
  public function getAbout() {
      return PolicyResource::collection(About::all());
}


}
