@props(['name', 'label', 'prefix', 'hint' => null])
<x-application.choice-group :name="$name" :label="$label" :prefix="$prefix" :hint="$hint" :options="['English' => 'English', 'Dutch' => 'Dutch', 'French' => 'French']" client-error="Choose at least one language to continue." />
