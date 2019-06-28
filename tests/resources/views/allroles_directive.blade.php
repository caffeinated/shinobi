@allroles('moderator', 'editor')
has both moderator and editor roles
@elseallroles('vip', 'premium')
has both VIP and premium roles
@else
does not have any of the defined roles
@endallroles