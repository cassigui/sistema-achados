<?php

namespace App\Modules\Base\Traits;

use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Trait de auditoria para registrar atividades em models Eloquent.
 *
 * Registra automaticamente eventos de criacao, atualizacao e exclusao
 * utilizando o pacote spatie/laravel-activitylog. Loga apenas campos
 * fillable que foram alterados.
 *
 * Por padrao, o prefixo de traducao segue o padrao "{nome_da_tabela}::toasts",
 * que funciona para modulos com estrutura de traducao simples (ex: Theme -> "themes::toasts.store").
 *
 * Para modulos com traducoes aninhadas (ex: Classe User.php aninhada no módulo Account), sobrescreva o metodo
 * getAuditTranslationPrefix() no model:
 *
 *     public function getAuditTranslationPrefix(): string
 *     {
 *         return 'account::toasts.users';
 *     }
 */
trait Auditable
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $causer = $activity->causer;

        if (empty($causer)) {
            return;
        }

        if (!empty($causer->authenticable_type)) {
            $activity->log_name = $causer->authenticable_type . '_' . $causer->authenticable_id;
        } else if ($causer->access_level_id == 1) {
            $activity->log_name = 'adm';
        }

        $activity->causer_type = "users";
    }

    public function getAuditTranslationPrefix(): string
    {
        return "{$this->getTable()}::toasts";
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        $prefix = $this->getAuditTranslationPrefix();
        switch ($eventName) {
            case 'created':
                return __("$prefix.store") . "[#{$this->id}]";

            case 'updated':
                return __("$prefix.update") . "[#{$this->id}]";

            case 'deleted':
                return __("$prefix.destroy") . "[#{$this->id}]";

            default:
                return $eventName;
        }
    }
}
