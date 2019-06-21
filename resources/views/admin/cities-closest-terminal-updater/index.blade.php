<p>
    Данное действие запустит процесс пересчёта ближайших терминалов для всех городов, <br>
    у которых в поле <strong>"Обновлять ближайший терминал"</strong> выбрано значение <strong>"Да"</strong>.
</p>
<hr>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal">
    Пересчитать ближайшие терминалы для городов
</button>

<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Подтверждение</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('cities-closest-terminal-update-action') }}" method="post">
                @csrf
                <div class="modal-body">
                    <h5>Вы уверены, что хотите пересчитать ближайшие терминалы для всех городов?</h5>
                    <p>
                        Данное действие запустит процесс пересчёта ближайших терминалов для всех городов,
                        у которых в поле <strong>"Обновлять ближайший терминал"</strong> выбрано значение <strong>"Да"</strong>.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Подтвердить</button>
                </div>
            </form>
        </div>
    </div>
</div>