<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Vote;
use Livewire\Component;
use App\Models\Position;
use App\Models\Candidate;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Events\VotedCandidate;
use Masmerise\Toaster\Toaster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\VotingRoom as VotingRoomModel;

class VotingRoom extends Component
{

    use WithPagination;

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
    ];

    // Mount component
    public function mount($id)
    {
        $this->totalStudents = User::where('role', 'user')->count();
        $this->loadRoom($id);
    }


public $voters = [];

// public function showVoters($roomId)
// {
//     $this->room = VotingRoomModel::findOrFail($roomId);

//     // Get voters for this room
//     $this->voters = Vote::with('user')
//         ->where('voting_rooms_id', $this->room->id)
//         ->get();

//     $this->modal('voters-list')->show();
// }


    // ────────────────────────────────────────────────
    // Voting Logic
    // ────────────────────────────────────────────────

    public function voteCandidate($candidateId)
    {
        $candidate = Candidate::with('position')->findOrFail($candidateId);
        $userId = Auth::id();
        
        $roomStatus = $candidate->position->votingRoom->status;

        if ($roomStatus !== "Ongoing"){
            Toaster::error("Voting is not available at this time.  Status: {$roomStatus}");
            return;
        }

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
            'voting_rooms_id' => $candidate->position->votingRoom->id,
            'candidate_id' => $candidate->id,
            'position_id'  => $candidate->position_id,
        ]);
        event(new VotedCandidate($candidate->position->votingRoom->id));
        $this->loadRoom();
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

    public $perPage = 5;

    public function voters()
    {
        return Vote::with('user')
        ->where('voting_rooms_id', $this->room->id)
        ->select('user_id') // select only user_id for uniqueness
        ->distinct()
        ->paginate(5);

    }

    // ────────────────────────────────────────────────
    // Room Status Update (auto sync with time)
    // ────────────────────────────────────────────────

    public function updateStatusIfNeeded()
    {
        $now = Carbon::now();

        if ($this->room->status !== 'Closed' && $now->greaterThanOrEqualTo($this->room->end_time)) {
            $this->room->status = 'Closed';
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
            'Closed'    => 'text-red-600 dark:text-red-400',
            default    => '',
        };
    }

    // ────────────────────────────────────────────────
    // Add Position
    // ────────────────────────────────────────────────

    protected $messages = [
    'newPosition.name.unique' => 'This position name already exists in this voting room.',
    'newCandidate.name.unique' => 'This candidate name already exists in this voting room.',
];


    public function createPosition()
    {
        $this->validate([
        'newPosition.name' => [
            'string',
            'required',
            Rule::unique('positions', 'name')
                ->where('voting_room_id', $this->room->id),
        ],
            'newPosition.order_index'  => 'nullable|integer|min:0',
        ]);

        $nextOrder = Position::where('voting_room_id', $this->room->id)->max('order_index') + 1;

        Position::create([
            'voting_room_id' => $this->room->id,
            'name'           => $this->newPosition['name'],
            'order_index'    => $this->newPosition['order_index'] ?? $nextOrder,
        ]);

        $this->reset('newPosition');
        Toaster::success('Position added successfully.');
        $this->modal('room-option')->close();
        $this->modal('add-positionOrcandidate')->close();
        $this->loadRoom();
        
    }

    // ────────────────────────────────────────────────
    // Add Candidate
    // ────────────────────────────────────────────────

    public function createCandidate()
    {
        $this->validate([
            'newCandidate.position_id' => 'required|exists:positions,id',
            'newCandidate.name' => [
            'string',
            'required',
            Rule::unique('candidates', 'name')
                ->where(fn ($q) => $q->where('position_id', $this->newCandidate['position_id'])),
            ],
            'newCandidate.short_name'  => 'nullable|string|max:50',
            'newCandidate.bio'         => 'nullable|string',
            'newCandidate.photo_url'   => 'nullable|url|max:255',

        ]);

        Candidate::create($this->newCandidate);

        $this->reset('newCandidate');
        $this->modal('room-option')->close();
        $this->modal('add-positionOrcandidate')->close();
        Toaster::success('Candidate added successfully.');
        $this->loadRoom();

    }

    // ────────────────────────────────────────────────
    // Room Options Modal
    // ────────────────────────────────────────────────

    public function roomOption()
    {
        $this->loadRoom();
        $this->modal('room-option')->show();
    }


    #[On('votedCandidate')]
    public function votedCandidate()
    {
        $this->loadRoom();
    }
    #[On('newUser')]
    public function newUser()
    {
        $this->loadRoom();
    }

    #[On('roomExpired')]
    public function roomExpired()
    {
        $this->loadRoom();
    }
    
    public Candidate|null $selectedCandidate = null;

    public function candidateCard($id)
    {
        $this->selectedCandidate = Candidate::findOrFail($id);
        $this->modal('candidate-card')->show();
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
