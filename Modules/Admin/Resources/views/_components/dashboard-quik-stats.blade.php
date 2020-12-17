<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content bg-arielle-smile">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading opacity-10">Participants Today</div>
                    <div class="widget-subheading opacity-8">Confirmed registrations in last 24 hours</div>
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span>{{ $participantsToday }}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content bg-grow-early">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading opacity-10">Participants Total</div>
                    <div class="widget-subheading opacity-8">Confirmed registrations</div>
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span>{{ $participantsTotal }}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content bg-premium-dark">
            <div class="widget-content-wrapper @if ($smsBalance > 0) text-warning @else text-danger @endif">
                <div class="widget-content-left">
                    <div class="widget-heading opacity-10">Sms Balance</div>
                    <div class="widget-subheading opacity-8">Updated every 5 minutes</div>
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers"><span>{{ $smsBalance }} sms</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
