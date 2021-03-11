<form method="post" action="{{ $url }}" name="simplepay" target="_self">
    @if($autoRedirect)
        <p>{{ __('You will be redirected to the secure payment page') }}</p>
        <p>
            <img src="{{ $url }}" alt="" title=""
                 onload="javascript:document.simplepay.submit()">
        </p>
    @endif
        <button type="submit">
            {{ __('Proceed to Payment') }}
        </button>
</form>
