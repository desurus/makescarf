<?php printf(__("You already logged in. <a href=\"%s\" title=\"Logout\">Logout</a>."), wp_logout_url($this->args()->get('redirect')));
