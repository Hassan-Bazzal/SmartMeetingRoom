<?php

namespace App\Policies;

use App\Models\Minute;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Employee;
use App\Models\User;

class MinutePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Minute  $minute
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Employee $employee, Minute $minute)
    {
         return $minute->booking->attendees()->where('user_id', $employee->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Employee $employee)
    {
        return \App\Models\Attendee::where('booking_id', $bookingId)
            ->where('user_id', $employee->id)
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Minute  $minute
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Minute $minute)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Minute  $minute
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Minute $minute)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Minute  $minute
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Minute $minute)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Minute  $minute
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Minute $minute)
    {
        //
    }
}
