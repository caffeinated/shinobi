@role('admin')
has admin role
@elserole('moderator')
has moderator role
@else
does not have admin or moderator roles
@endrole