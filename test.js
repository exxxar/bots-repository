/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Получение списка пользователей",
 *     @OA\Response(
 *         response=200,
 *         description="Список пользователей",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
 *     )
 * )
 */
public function index() {
    return response()->json(User::all());
}
