{% if post.conf_user is defined and updateIniFileLogUser == true %}
    <div class="alert alert-success">Vos préférences ont été mises à jour avec succès.</div>
{% elseif post.conf_user is defined and updateIniFileLogUser == false %}
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>Impossible de mettre à jour votre configuration !</h4>
        <ul class="text-danger">
            <li>Vérifiez si vous avez les droits d'écriture</li>
        </ul>
    </div>
{% endif %}
