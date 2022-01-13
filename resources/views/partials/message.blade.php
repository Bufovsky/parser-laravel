@if(session('message'))
    <script>
        let message = '{{ Session::get('message') }}';
        let info = message.split('|');

        switch (info[0]) {
            case 'info':
                Qual.info(info[0], info[1]);
                break;
            case 'error':
                Qual.error(info[0], info[1]);
                break;
            case 'success':
                Qual.success(info[0], info[1]);
                setTimeout(function (){$('.alertcontainer').hide()}, 5000);
                break;
        }
    </script>
@endif