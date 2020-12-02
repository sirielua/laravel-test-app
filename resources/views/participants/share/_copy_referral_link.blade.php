<div class="input-group mb-2">
    <label class="sr-only" for="referralLink">Your Referral Link</label>
    <input type="text" value="{{ $url}}" class="form-control mr-sm-2" id="referralLink" readonly>

    <button class="btn btn-primary" id="copyRerferralLink">Copy</button>
</div>

@push('scripts')
    <script type="text/javascript">
        var copyBtn = document.querySelector('#copyRerferralLink');

        copyBtn.addEventListener('click', function(event) {
            var copyInput = document.querySelector('#referralLink');
            copyInput.focus();
            copyInput.select();

            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successful' : 'unsuccessful';
                //console.log('Copying text command was ' + msg);
                alert('Referral link copied to clipboard');
            } catch (err) {
                //console.log('Oops, unable to copy');
                alert('Error');
            }
        });
    </script>
@endpush
