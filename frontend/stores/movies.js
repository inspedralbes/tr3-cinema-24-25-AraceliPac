// stores/movies.js
import { defineStore } from 'pinia';

export const useMoviesStore = defineStore('movies', {
    state: () => ({
        movies: [],
        currentMovie: null,
        featuredMovies: [],
        isLoading: false,
        error: null,
        filters: {
            genre: null,
            search: ''
        },
        // Cache de actores por película
        movieActors: {}
    }),

    getters: {
        // Obtiene todas las películas
        allMovies: (state) => state.movies,

        // Obtiene las películas destacadas
        highlightedMovies: (state) => state.featuredMovies,

        // Comprueba si hay películas cargadas
        hasMovies: (state) => state.movies.length > 0,

        // Filtra películas por género y/o búsqueda
        filteredMovies: (state) => {
            let result = [...state.movies];

            // Filtrar por género si se seleccionó uno
            if (state.filters.genre) {
                result = result.filter(movie => movie.genre_id === state.filters.genre);
            }

            // Filtrar por texto de búsqueda
            if (state.filters.search.trim()) {
                const searchTerm = state.filters.search.toLowerCase().trim();
                result = result.filter(movie =>
                    movie.title.toLowerCase().includes(searchTerm) ||
                    movie.description.toLowerCase().includes(searchTerm)
                );
            }

            return result;
        },

        // Obtiene todos los géneros de las películas
        allGenres: (state) => {
            const uniqueGenres = new Map();

            state.movies.forEach(movie => {
                if (movie.genre && !uniqueGenres.has(movie.genre.id)) {
                    uniqueGenres.set(movie.genre.id, movie.genre);
                }
            });

            return Array.from(uniqueGenres.values());
        },

        // Obtiene los actores de una película específica del cache
        getActorsByMovieId: (state) => (movieId) => {
            return state.movieActors[movieId] || [];
        }
    },

    actions: {
        // Establece el filtro de género
        setGenreFilter(genreId) {
            this.filters.genre = genreId;
        },

        // Establece el texto de búsqueda
        setSearchFilter(searchText) {
            this.filters.search = searchText;
        },

        // Limpia todos los filtros
        clearFilters() {
            this.filters.genre = null;
            this.filters.search = '';
        },

        // Obtiene todas las películas del API
        async fetchMovies() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await fetch('http://cinema.daw.inspedralbes.cat/tr3-cinema-24-25-AraceliPac/backend/public/api/movies');

                if (response.status === 200) {
                    const data = await response.json();
                    this.movies = data;

                    // Seleccionar algunas películas como destacadas (las 3 primeras por ejemplo)
                    this.featuredMovies = data.slice(0, 3);
                } else {
                    throw new Error('Error obteniendo las películas');
                }
            } catch (error) {
                this.error = error.message || 'Error desconocido';
                console.error('Error en fetchMovies:', error);
            } finally {
                this.isLoading = false;
            }
        },

        // Obtiene una película específica por ID
        async fetchMovieById(movieId) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await fetch(`http://cinema.daw.inspedralbes.cat/tr3-cinema-24-25-AraceliPac/backend/public/api/movies/${movieId}`);

                if (response.status === 200) {
                    const data = await response.json();
                    this.currentMovie = data;

                    // Si la película ya tiene actores básicos en la respuesta, los guardamos
                    if (data.actors && Array.isArray(data.actors)) {
                        this.movieActors[movieId] = data.actors;
                    }
                } else {
                    throw new Error('Película no encontrada');
                }
            } catch (error) {
                this.error = error.message || 'Error desconocido';
                console.error('Error en fetchMovieById:', error);
            } finally {
                this.isLoading = false;
            }
        },

        // Obtiene actores de una película específica usando el endpoint específico
        async fetchMovieActors(movieId) {
            // Si ya tenemos los actores en cache, no hacemos la petición
            if (this.movieActors[movieId] && this.movieActors[movieId].length > 0) {
                return this.movieActors[movieId];
            }

            this.isLoading = true;

            try {
                const response = await fetch(`http://cinema.daw.inspedralbes.cat/tr3-cinema-24-25-AraceliPac/backend/public/api/movies/${movieId}/actors`);

                if (response.status === 200) {
                    const data = await response.json();

                    // Guardamos en el cache
                    this.movieActors[movieId] = data;

                    // Si tenemos la película actual cargada y es la misma, actualizamos sus actores
                    if (this.currentMovie && this.currentMovie.id === movieId) {
                        this.currentMovie.actors = data;
                    }

                    return data;
                } else {
                    throw new Error('Error obteniendo actores de la película');
                }
            } catch (error) {
                console.error('Error en fetchMovieActors:', error);
                return [];
            } finally {
                this.isLoading = false;
            }
        },

        // Busca películas que coincidan con un término de búsqueda
        async searchMovies(searchTerm) {
            this.isLoading = true;
            this.error = null;

            try {
                // Podemos implementar la búsqueda en el frontend para evitar peticiones
                // si ya tenemos todas las películas cargadas
                if (this.hasMovies && searchTerm) {
                    const searchTermLower = searchTerm.toLowerCase().trim();
                    const results = this.movies.filter(movie =>
                        movie.title.toLowerCase().includes(searchTermLower) ||
                        movie.description.toLowerCase().includes(searchTermLower)
                    );
                    return results;
                }

                // Si no tenemos las películas o el término está vacío, hacemos una búsqueda general
                const response = await fetch(`http://cinema.daw.inspedralbes.cat/tr3-cinema-24-25-AraceliPac/backend/public/api/movies`);

                if (response.status === 200) {
                    const data = await response.json();
                    return data;
                } else {
                    throw new Error('Error en la búsqueda de películas');
                }
            } catch (error) {
                this.error = error.message || 'Error desconocido';
                console.error('Error en searchMovies:', error);
                return [];
            } finally {
                this.isLoading = false;
            }
        },

        // Obtiene películas por género
        async fetchMoviesByGenre(genreId) {
            // Podemos filtrar localmente si ya tenemos todas las películas
            if (this.hasMovies && genreId) {
                return this.movies.filter(movie => movie.genre_id === genreId);
            }

            this.isLoading = true;
            this.error = null;

            try {
                // Como no tienes un endpoint específico para filtrar por género,
                // obtenemos todas y filtramos en el frontend
                const response = await fetch(`http://cinema.daw.inspedralbes.cat/tr3-cinema-24-25-AraceliPac/backend/public/api/movies`);

                if (response.status === 200) {
                    const data = await response.json();

                    // Actualizamos nuestro estado con todas las películas
                    this.movies = data;

                    // Devolvemos solo las del género solicitado
                    return data.filter(movie => movie.genre_id === genreId);
                } else {
                    throw new Error('Error obteniendo películas por género');
                }
            } catch (error) {
                this.error = error.message || 'Error desconocido';
                console.error('Error en fetchMoviesByGenre:', error);
                return [];
            } finally {
                this.isLoading = false;
            }
        },

        // Obtiene las películas destacadas
        async fetchFeaturedMovies() {
            // Si ya tenemos películas, podemos seleccionar algunas como destacadas
            if (this.hasMovies) {
                // Por ejemplo, las 3 primeras o podríamos implementar alguna lógica de selección
                this.featuredMovies = this.movies.slice(0, 3);
                return this.featuredMovies;
            }

            // Si no tenemos películas, las cargamos primero
            await this.fetchMovies();

            // Seleccionamos algunas como destacadas
            this.featuredMovies = this.movies.slice(0, 3);
            return this.featuredMovies;
        }
    }
});