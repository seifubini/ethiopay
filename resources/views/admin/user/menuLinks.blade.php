<div class="tabs-container" id="tabmenu">
    <ul class="nav nav-tabs">
        <li class="{{ (Route::currentRouteName() == 'admin.users.show' ? 'active' : '') }}"><a href="{{ url("admin/users/$user->id") }}">General</a></li>
        <li class="{{ (in_array(Route::currentRouteName(), array('admin.users.transactions.list', 'admin.users.transactions.show')) ? 'active' : '') }}"><a href="{{ url("admin/users/$user->id/transactions") }}">Transaction History</a></li>
        <li class="{{ (in_array(Route::currentRouteName(), array('admin.users.payment-methods.list', 'admin.users.payment-methods.create')) ? 'active' : '') }}"><a href="{{ url("admin/users/$user->id/payment-methods") }}">Payment Methods</a></li>
        <li class="{{ (in_array(Route::currentRouteName(), array('admin.users.tickets.list', 'admin.users.tickets.show')) ? 'active' : '') }}"><a href="{{ url("admin/users/$user->id/tickets") }}">Support Tickets</a></li>
    </ul>
</div>