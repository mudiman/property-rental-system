<li class="treeview">
    <a href="#"><i class="fa fa-user"></i><span >User Menu</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('users*') ? 'active' : '' }}">
            <a href="{!! route('users.index') !!}"><i class="fa fa-user"></i><span>Users</span></a>
        </li>
        <li class="{{ Request::is('devices*') ? 'active' : '' }}">
            <a href="{!! route('devices.index') !!}"><i class="fa fa-phone"></i><span>Devices</span></a>
        </li>
        <li class="{{ Request::is('userServices*') ? 'active' : '' }}">
            <a href="{!! route('userServices.index') !!}"><i class="fa fa-cloud"></i><span>UserServices</span></a>
        </li>
        <li class="{{ Request::is('offers*') ? 'active' : '' }}">
          <a href="{!! route('offers.index') !!}"><i class="fa fa-envelope"></i><span>Offers</span></a>
        </li>
        <li class="{{ Request::is('scores*') ? 'active' : '' }}">
            <a href="{!! route('scores.index') !!}"><i class="fa fa-edit"></i><span>Scores</span></a>
        </li>
        <li class="{{ Request::is('tenancies*') ? 'active' : '' }}">
          <a href="{!! route('tenancies.index') !!}"><i class="fa fa-envelope"></i><span>Tenancies</span></a>
        </li>
        <li class="{{ Request::is('events*') ? 'active' : '' }}">
            <a href="{!! route('events.index') !!}"><i class="fa fa-edit"></i><span>Events</span></a>
        </li>
    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-tags"></i><span>Property Pro</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      <li class="{{ Request::is('agencies*') ? 'active' : '' }}">
          <a href="{!! route('agencies.index') !!}"><i class="fa fa-edit"></i><span>Agencies</span></a>
      </li>
      <li class="{{ Request::is('agents*') ? 'active' : '' }}">
          <a href="{!! route('agents.index') !!}"><i class="fa fa-edit"></i><span>Agents</span></a>
      </li>
      <li class="{{ Request::is('propertyPros*') ? 'active' : '' }}">
          <a href="{!! route('propertyPros.index') !!}"><i class="fa fa-education"></i><span>PropertyPros</span></a>
      </li>
      </li><li class="{{ Request::is('propertyServices*') ? 'active' : '' }}">
        <a href="{!! route('propertyServices.index') !!}"><i class="fa fa-edit"></i><span>PropertyServices</span></a>
    </li>
    </ul>
</li>
<li class="treeview">
    <a href="#"><i class="fa fa-home"></i><span>Property Menu</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('properties*') ? 'active' : '' }}">
            <a href="{!! route('properties.index') !!}"><i class="fa fa-home"></i><span>Properties</span></a>
        </li>
        <li class="{{ Request::is('viewings*') ? 'active' : '' }}">
            <a href="{!! route('viewings.index') !!}"><i class="fa fa-calendar"></i><span>Viewings</span></a>
        </li>
        <li class="{{ Request::is('viewingRequests*') ? 'active' : '' }}">
            <a href="{!! route('viewingRequests.index') !!}"><i class="fa fa-th-list"></i><span>ViewingRequests</span></a>
        </li>
        <li class="{{ Request::is('premiumListings*') ? 'active' : '' }}">
            <a href="{!! route('premiumListings.index') !!}"><i class="fa fa-list-alt"></i><span>PremiumListings</span></a>
        </li>
        <li class="{{ Request::is('statistics*') ? 'active' : '' }}">
            <a href="{!! route('statistics.index') !!}"><i class="fa fa-stats"></i><span>Statistics</span></a>
        </li>
    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-book"></i><span>Assets</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('documents*') ? 'active' : '' }}">
            <a href="{!! route('documents.index') !!}"><i class="fa fa-book"></i><span>Documents</span></a>
        </li>
        <li class="{{ Request::is('images*') ? 'active' : '' }}">
            <a href="{!! route('images.index') !!}"><i class="fa fa-camera"></i><span>Images</span></a>
        </li>

    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-credit-card"></i><span>Payments</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('transactions*') ? 'active' : '' }}">
            <a href="{!! route('transactions.index') !!}"><i class="fa fa-gbp"></i><span>Transactions</span></a>
        </li>

        <li class="{{ Request::is('payins*') ? 'active' : '' }}">
            <a href="{!! route('payins.index') !!}"><i class="fa fa-credit-card"></i><span>Payins</span></a>
        </li>

        <li class="{{ Request::is('payouts*') ? 'active' : '' }}">
            <a href="{!! route('payouts.index') !!}"><i class="fa fa-credit-card"></i><span>Payouts</span></a>
        </li>
    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-inbox"></i><span>Messaging</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('messages*') ? 'active' : '' }}">
            <a href="{!! route('messages.index') !!}"><i class="fa fa-inbox"></i><span>Messages</span></a>
        </li>
        <li class="">
            <a href="{!! route('messages.client') !!}"><i class="fa fa-inbox"></i><span>Messages Client</span></a>
        </li>
        <li class="{{ Request::is('threads*') ? 'active' : '' }}">
            <a href="{!! route('threads.index') !!}"><i class="fa fa-inbox"></i><span>Threads</span></a>
        </li>

        <li class="{{ Request::is('participants*') ? 'active' : '' }}">
            <a href="{!! route('participants.index') !!}"><i class="fa fa-edit"></i><span>Participants</span></a>
        </li>
    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-inbox"></i><span>Interactions</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('references*') ? 'active' : '' }}">
            <a href="{!! route('references.index') !!}"><i class="fa fa-edit"></i><span>References</span></a>
        </li>
        <li class="{{ Request::is('likes*') ? 'active' : '' }}">
            <a href="{!! route('likes.index') !!}"><i class="fa fa-edit"></i><span>Likes</span></a>
        </li>
        <li class="{{ Request::is('reviews*') ? 'active' : '' }}">
            <a href="{!! route('reviews.index') !!}"><i class="fa fa-comment"></i><span>Reviews</span></a>
        </li>
        <li class="{{ Request::is('reports*') ? 'active' : '' }}">
            <a href="{!! route('reports.index') !!}"><i class="fa fa-list-alt"></i><span>Reports</span></a>
        </li>
        <li class="{{ Request::is('feedback*') ? 'active' : '' }}">
            <a href="{!! route('feedback.index').'?sort=created_at-dir-desc'; !!}"><i class="fa fa-comment"></i><span>Feedback</span></a>
        </li>
    </ul>
</li>

<li class="treeview">
    <a href="#"><i class="fa fa-tags"></i><span>Metadata update</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('services*') ? 'active' : '' }}">
            <a href="{!! route('services.index') !!}"><i class="fa fa-cog"></i><span>Services</span></a>
        </li>
        <li class="{{ Request::is('scoreTypes*') ? 'active' : '' }}">
            <a href="{!! route('scoreTypes.index') !!}"><i class="fa fa-edit"></i><span>ScoreTypes</span></a>
        </li>
        <li class="{{ Request::is('documentTypes*') ? 'active' : '' }}">
            <a href="{!! route('documentTypes.index') !!}"><i class="fa fa-duplicate"></i><span>DocumentTypes</span></a>
        </li>
        <li class="{{ Request::is('serviceTypes*') ? 'active' : '' }}">
            <a href="{!! route('serviceTypes.index') !!}"><i class="fa fa-transfer"></i><span>ServiceTypes</span></a>
        </li>
        <li class="{{ Request::is('serviceFeeTypes*') ? 'active' : '' }}">
            <a href="{!! route('serviceFeeTypes.index') !!}"><i class="fa fa-usd"></i><span>ServiceFeeTypes</span></a>
        </li>
        <li class="{{ Request::is('bathroomTypes*') ? 'active' : '' }}">
            <a href="{!! route('bathroomTypes.index') !!}"><i class="fa fa-piggy-bank"></i><span>BathroomTypes</span></a>
        </li>
        <li class="{{ Request::is('paymentMethods*') ? 'active' : '' }}">
            <a href="{!! route('paymentMethods.index') !!}"><i class="fa fa-usd"></i><span>PaymentMethods</span></a>
        </li>
        <li class="{{ Request::is('lettingTypes*') ? 'active' : '' }}">
            <a href="{!! route('lettingTypes.index') !!}"><i class="fa fa-tags"></i><span>LettingTypes</span></a>
        </li>
        <li class="{{ Request::is('propertyRoomTypes*') ? 'active' : '' }}">
            <a href="{!! route('propertyRoomTypes.index') !!}"><i class="fa fa-bed"></i><span>PropertyRoomTypes</span></a>
        </li>
        <li class="{{ Request::is('extras*') ? 'active' : '' }}">
            <a href="{!! route('extras.index') !!}"><i class="fa fa-check"></i><span>Extras</span></a>
        </li>
        <li class="{{ Request::is('roles*') ? 'active' : '' }}">
            <a href="{!! route('roles.index') !!}"><i class="fa fa-user"></i><span>Roles</span></a>
        </li>
        <li class="{{ Request::is('histories*') ? 'active' : '' }}">
          <a href="{!! route('histories.index') !!}"><i class="fa fa-edit"></i><span>Histories</span></a>
      </li>
    </ul>
    <li class="{{ Request::is('settings*') ? 'active' : '' }}">
        <a href="{!! route('settings.edit', 1) !!}"><i class="fa fa-edit"></i><span>Settings</span></a>
    </li>
</li>