<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Vote;
use Livewire\Component;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\VotingRoom as VotingRoomModel;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Auth;

class VotingRoom extends Component
{
    public $room;
    public $positions = [];
    public $totalStudents = 0;

    // New position form
    public $newPosition = [
        'name' => '',
        'order_index' => 0,
    ];

    // New candidate form
    public $newCandidate = [
        'position_id' => null,
        'name' => '',
        'short_name' => '',
        'bio' => '',
        'photo_url' => '',
        'color' => '#3B82F6',
    ];

    // Mount component
    public function mount($id)
    {
        $this->totalStudents = User::where('role', 'user')->count();
        $this->loadRoom($id);
    }

    // ────────────────────────────────────────────────
    // Voting Logic
    // ────────────────────────────────────────────────

    public function voteCandidate($candidateId)
    {
        $candidate = Candidate::with('position')->findOrFail($candidateId);
        $userId = Auth::id();

        // Prevent duplicate votes for the same position
        $alreadyVoted = Vote::where('user_id', $userId)
            ->where('position_id', $candidate->position_id)
            ->exists();

        if ($alreadyVoted) {
            Toaster::error('You already voted for this position.');
            return;
        }

        Vote::create([
            'user_id'      => $userId,
            'candidate_id' => $candidate->id,
            'position_id'  => $candidate->position_id,
        ]);

        Toaster::success('Vote cast successfully!');
    }

    // ────────────────────────────────────────────────
    // Room Loader & Live Vote Counts
    // ────────────────────────────────────────────────

    public function loadRoom($id = null)
    {
        $roomId = $id ?? $this->room->id;

        $this->room = VotingRoomModel::with([
            'positions' => fn($query) => $query->orderBy('order_index'),
            'positions.candidates.votes'
        ])->findOrFail($roomId);

        $this->updateStatusIfNeeded();

        $this->positions = $this->room->positions;

        // Count votes per candidate
        foreach ($this->positions as $position) {
            foreach ($position->candidates as $candidate) {
                $candidate->vote_count = $candidate->votes->count();
            }
        }
    }

    // ────────────────────────────────────────────────
    // Room Status Update (auto sync with time)
    // ────────────────────────────────────────────────

    public function updateStatusIfNeeded()
    {
        $now = Carbon::now();

        if ($this->room->status !== 'Ended' && $now->greaterThanOrEqualTo($this->room->end_time)) {
            $this->room->status = 'Ended';
        } elseif ($this->room->status !== 'Ongoing' && $now->between($this->room->start_time, $this->room->end_time)) {
            $this->room->status = 'Ongoing';
        } elseif ($this->room->status !== 'Pending' && $now->lessThan($this->room->start_time)) {
            $this->room->status = 'Pending';
        }

        $this->room->save();
    }

    public function getStatusTextColorProperty()
    {
        return match ($this->room->status) {
            'Pending'  => 'text-yellow-600 dark:text-yellow-400',
            'Ongoing'  => 'text-green-600 dark:text-green-400',
            'Ended'    => 'text-red-600 dark:text-red-400',
            default    => '',
        };
    }

    // ────────────────────────────────────────────────
    // Add Position
    // ────────────────────────────────────────────────

    public function addPosition()
    {
        $this->modal('add-position')->show();
    }

    public function createPosition()
    {
        $this->validate([
            'newPosition.name'         => 'required|string|max:255',
            'newPosition.order_index'  => 'nullable|integer|min:0',
        ]);

        $nextOrder = Position::where('voting_room_id', $this->room->id)->max('order_index') + 1;

        Position::create([
            'voting_room_id' => $this->room->id,
            'name'           => $this->newPosition['name'],
            'order_index'    => $this->newPosition['order_index'] ?? $nextOrder,
        ]);

        $this->reset('newPosition');
        $this->modal('add-position')->close();

        $this->loadRoom();
        Toaster::success('Position added successfully.');
    }

    // ────────────────────────────────────────────────
    // Add Candidate
    // ────────────────────────────────────────────────

    public function addCandidate()
    {
        $this->modal('add-candidate')->show();
    }

    public function createCandidate()
    {
        $this->validate([
            'newCandidate.position_id' => 'required|exists:positions,id',
            'newCandidate.name'        => 'required|string|max:255',
            'newCandidate.short_name'  => 'nullable|string|max:50',
            'newCandidate.bio'         => 'nullable|string',
            'newCandidate.photo_url'   => 'nullable|url|max:255',
            'newCandidate.color'       => 'nullable|string|max:20',
        ]);

        Candidate::create($this->newCandidate);

        $this->reset('newCandidate');
        $this->modal('add-candidate')->close();

        $this->loadRoom();
        Toaster::success('Candidate added successfully.');
    }

    // ────────────────────────────────────────────────
    // Room Options Modal
    // ────────────────────────────────────────────────

    public function roomOption()
    {
        $this->loadRoom();
        $this->modal('room-option')->show();
    }

    // ────────────────────────────────────────────────
    // Render Component
    // ────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.voting-room', [
            'room'      => $this->room,
            'positions' => $this->positions,
        ]);
    }
}
