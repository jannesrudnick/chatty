<div class="container top-space">
    <div class="row">
        <div class="col-sm-4">
            <h3>Empfangen</h3>
            <p>Hier siehst du alle Nachrichten die dir zugeschickt wurden.</p>
        </div>
        <div class="col-sm-4">
            <h3>Senden</h3>
            <p>Hier kannst du Nachrichten senden.</p>
            <form action="./index.php?do=1" method="post" class="top-space">
                <select multiple class="form-control" name="receiver" required>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <?php 
                    // TODO: db all users
                    ?>
                </select>
                
                <input type="text" class="form-control" placeholder="Betreff eingeben" name="subject" required>
                <textarea class="form-control" rows="5" placeholder="Nachricht eingeben" name="text" required></textarea>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="col-sm-4">
            <h3>Gesendet</h3>
            <p>Hier siehst du alle Nachrichten die du verschickt hast.</p>
        </div>
    </div>
</div>