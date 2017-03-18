{% if post.reboot is defined and rebootRtorrent.statusReboot == 0 %}
    <div class="alert alert-success">Votre session rtorrent a été redémarrée avec succès.</div>
{% elseif post.reboot is defined and rebootRtorrent.statusReboot != 0 %}
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Un problème est survenu lors du redémarrage de rtorrent</strong>, vérifiez votre configuration.
    </div>
{% endif %}
{% if post.reboot is defined and rebootRtorrent.statusReboot == 0 %}
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>Log :</h4>
        <strong>commande exécutée : </strong> {{ rebootRtorrent.command }}<br>
        <strong>statut :</strong> {{ rebootRtorrent.statusReboot }}<br>
        <strong>résultat de la commande :</strong> <br>
        {% for logs in rebootRtorrent.logReboot %}
            # {{ logs }}<br>
        {% endfor %}
    </div>
{% endif %}

{% if serveur.CheckUpdate != false %}
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>Nouvelle version disponible ! (v{{ serveur.CheckUpdate.version }})</h4>
        <ul>{% for new in serveur.CheckUpdate.news %}<li class="text-info">{{ new }}</li>{% endfor %}</ul>
        <small style="display:block;margin-top:5px;">Participants: {{ serveur.CheckUpdate.contributors }}</small>
    </div>
{% endif %}
