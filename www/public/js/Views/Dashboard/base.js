export const classNames = {
    acceptGroupInvite: 'accept-group-invite',
    declineGroupInvite: 'decline-group-invite',
    groupInviteElement: 'group-invite'
};

export const idNames = {
    groupInvites: 'group-invites'
};

export const selectors = {
    acceptGroupInvite: `.${classNames.acceptGroupInvite}`,
    declineGroupInvite: `.${classNames.declineGroupInvite}`,
    groupInvites: `#${idNames.groupInvites}`,
    groupInviteElement: `.${classNames.groupInviteElement}`
};

export const elements = {
    groupInvitesWrapper: document.getElementById(idNames.groupInvites)
};

export const apiRoutes = {
    acceptGroupInvite: '/api/v1/dashboard/acceptGroupInvite',
    declineGroupInvite: '/api/v1/dashboard/declineGroupInvite'
};
