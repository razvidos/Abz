<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected static string $default_Photo = 'storage/avatars/1/user-photo-0.jpg';
    protected static int $countPerPage = 6;


    /**
     * @return string
     */
    private function getDefaultPhoto(): string
    {
        return self::$default_Photo;
    }

    /**
     * @return int
     */
    protected function getCountPerPage(): int
    {
        return self::$countPerPage;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View|string
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        if ($request->missing('page')) {
            $request->request->add(['page' => 1]);
        }

        $count = $this->getCountPerPage();
        $page = $request->request->get('page');
        $request->request->add(['count' => $count]);
        $request->request->add(['offset' => $count * ($page - 1)]);

        $response = (new API\UserController)->index($request);
        if (!$response) {
            return $response;
        }
        $paginator = json_decode($response->getContent());

        foreach ($paginator->users as $index => $user) {
            if (!$user->photo) {
                $paginator->users[$index]->photo = $this->getDefaultPhoto();
            }
        }

        if ($request->ajax()) {
            $html = '';
            foreach ($paginator->users as $user) {
                $html .= '<tr>'
                    . "<td>$user->id</td>"
                    . "<td><img src=\"" . asset($user->photo) . '" alt=\"\" width="30px" style="border-radius: 50%;">'
                    . '<a href="' . route('users.show', $user->id) . "\">$user->name</a>"
                    . '</td>'
                    . "<td>$user->email</td>"
                    . "<td>$user->phone</td>"
                    . "<td>$user->position_id</td>"
                    . '</tr>';
            }
            return $html;
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $response = (new API\TokenController)->getToken();
        $cookies = $response->headers->getCookies();
        foreach ($cookies as $cookie) {
            Cookie::queue(
                $cookie->getName(),
                $cookie->getValue(),
                API\TokenController::getMinutesToExpires()
            );
        }
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return View|Response
     */
    public function store(Request $request)
    {
        $response = (new API\UserController)->store($request);
        $content = json_decode($response->getContent());
        if (!$content->success) {
            return $response;
        }
        return $this->show(User::find($content->user_id));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        if (!$user->photo) {
            $user->photo = $this->getDefaultPhoto();
        }
        return view('users.show', compact('user'));
    }
}
