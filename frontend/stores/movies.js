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
        }
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
                const response = await fetch('http://localhost:8000/api/movies');

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
                const response = await fetch(`http://localhost:8000/api/movies/${movieId}`);

                if (response.status === 200) {
                    const data = await response.json();
                    this.currentMovie = data;
                } else {
                    throw new Error('Pel·lícula no trobada');
                }
            } catch (error) {
                this.error = error.message || 'Error desconocido';
                console.error('Error en fetchMovieById:', error);
            } finally {
                this.isLoading = false;
            }
        },

        // Busca películas que coincidan con un término de búsqueda
        async searchMovies(searchTerm) {
            this.isLoading = true;
            this.error = null;

            try {
                // Podemos usar el endpoint de búsqueda si tu API lo proporciona
                // o alternativamente filtrar las películas ya descargadas
                const response = await fetch(`http://localhost:8000/api/movies?search=${encodeURIComponent(searchTerm)}`);

                if (response.status === 200) {
                    const data = await response.json();
                    // No sobrescribimos todas las películas, solo establecemos el resultado de búsqueda
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
            this.isLoading = true;
            this.error = null;

            try {
                // Podemos usar un endpoint específico si tu API lo proporciona
                const response = await fetch(`http://localhost:8000/api/genres/${genreId}/movies`);

                if (response.status === 200) {
                    const data = await response.json();
                    return data;
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

        // Obtiene las películas recientes o destacadas para mostrar en inicio
        async fetchFeaturedMovies() {
            this.isLoading = true;
            this.error = null;

            try {
                // Si tu API tiene un endpoint específico para películas destacadas o recientes, úsalo aquí
                const response = await fetch('http://localhost:8000/api/movies?featured=1');

                if (response.status === 200) {
                    const data = await response.json();
                    this.featuredMovies = data;
                } else {
                    throw new Error('Error obteniendo películas destacadas');
                }
            } catch (error) {
                this.error = error.message || 'Error desconocido';
                console.error('Error en fetchFeaturedMovies:', error);

                // Si falla, intentamos usar las películas ya cargadas
                if (this.movies.length > 0) {
                    this.featuredMovies = this.movies.slice(0, 3);
                }
            } finally {
                this.isLoading = false;
            }
        }
    }
});