<?php

    namespace App\User;

    class Admin implements Role
    {
        public function AddUserModal() 
        {
            $modaldetails = array 
                (
                    '[modal.title}' => 'Staff Details',
                    '{add_button.title}' => 'Add Staff'
                );
        }
    }
?>