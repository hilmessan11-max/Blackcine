<div class="filters">
    <form method="GET">
        <select name="genre">
            <option value="">Tous les genres</option>
            @foreach($genres as $g)
                <option value="{{ $g }}" {{ request('genre') == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
        </select>
        <select name="category">
            <option value="">Toutes catégories</option>
            <!-- remplir selon films/series -->
        </select>
        <button type="submit">Filtrer</button>
    </form>
</div>