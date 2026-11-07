@if ($applicationsOpen && $definition->followsEditionStatus)
    <x-application-deadline :edition="$edition" class="application__context" />
@endif
@include('audiences.'.$audience.'.context')
