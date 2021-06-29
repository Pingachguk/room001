@component('mail::message')
# Заявка на подтверждение паспорта
# От: {{ $data->phone }}

{{ $data->body }}
@endcomponent
