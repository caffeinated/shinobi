@anyrole('moderator', 'admin')
has either moderator or admin role
@elseanyrole('editor', 'contributor')
has either editor or contributor role
@else
does not have any of the defined roles
@endanyrole