@extends('layouts.mmm')

@section('titre', 'Gestion des employés - ZeDuc@Space')

@section('contenu')
<div class="employees-management-wrapper">

    <!-- En-tête : Titre + Sous-titre + Bouton d'action -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="font-serif fw-bold text-dark mb-1" style="font-size: 28px; font-family: Georgia, serif;">Gestion des employés</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Ajoutez, modifiez ou supprimez les comptes étudiants de l'application.</p>
        </div>
        <div>
            <a href="{{ route('admin.employees.create') ?? '#' }}" class="btn text-white px-3 py-2 fw-medium shadow-sm" style="background-color: #a68a56; border: none; border-radius: 6px; font-size: 13px;">
                + Ajouter un étudiant
            </a>
        </div>
    </div>

    <!-- Barre de filtres et recherche -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="m-0">
                    <div class="input-group border-0">
                        <span class="input-group-text bg-transparent border-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-transparent shadow-none" placeholder="Rechercher un étudiant (nom, email...)" style="font-size: 13.5px;">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select border-0 shadow-sm rounded-3 p-2 bg-white text-muted" style="font-size: 13.5px;">
                <option value="">Tous les rôles</option>
                <option value="Employé">Employé</option>
                <option value="Gérant">Gérant</option>
                <option value="Administrateur">Administrateur</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select border-0 shadow-sm rounded-3 p-2 bg-white text-muted" style="font-size: 13.5px;">
                <option value="">Tous les statuts</option>
                <option value="Actif">Actif</option>
                <option value="Inactif">Inactif</option>
            </select>
        </div>
    </div>

    <!-- Tableau de la liste des employés -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead style="background-color: #1a1a1a; color: #ffffff;">
                    <tr class="text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">
                        <th class="py-3 px-4">Nom et prénom</th>
                        <th class="py-3 px-3">Email <i class="bi bi-arrow-down-up ms-1" style="font-size: 10px;"></i></th>
                        <th class="py-3 px-3">Rôle <i class="bi bi-arrow-down-up ms-1" style="font-size: 10px;"></i></th>
                        <th class="py-3 px-3">Statut <i class="bi bi-arrow-down-up ms-1" style="font-size: 10px;"></i></th>
                        <th class="py-3 px-3">Date d'ajout <i class="bi bi-arrow-down-up ms-1" style="font-size: 10px;"></i></th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees ?? [
                        (object)['id'=>1, 'nom'=>'Jean Dupont', 'email'=>'jean.dupont@monmiam.com', 'role'=>'Employé', 'statut'=>'Actif', 'date'=>'12/03/2025 14:32'],
                        (object)['id'=>2, 'nom'=>'Marie Laurent', 'email'=>'marie.laurent@monmiam.com', 'role'=>'Employé', 'statut'=>'Actif', 'date'=>'10/03/2025 09:15'],
                        (object)['id'=>3, 'nom'=>'Paul Ngoma', 'email'=>'paul.ngoma@monmiam.com', 'role'=>'Gérant', 'statut'=>'Actif', 'date'=>'08/03/2025 16:47'],
                        (object)['id'=>4, 'nom'=>'Sophie Martin', 'email'=>'sophie.martin@monmiam.com', 'role'=>'Employé', 'statut'=>'Actif', 'date'=>'05/03/2025 11:20'],
                        (object)['id'=>5, 'nom'=>'David Okala', 'email'=>'david.okala@monmiam.com', 'role'=>'Employé', 'statut'=>'Inactif', 'date'=>'02/03/2025 08:30'],
                        (object)['id'=>6, 'nom'=>'Fatou Diallo', 'email'=>'fatou.diallo@monmiam.com', 'role'=>'Administrateur', 'statut'=>'Actif', 'date'=>'28/02/2025 10:25'],
                        (object)['id'=>7, 'nom'=>'Ahmed Bello', 'email'=>'ahmed.bello@monmiam.com', 'role'=>'Employé', 'statut'=>'Actif', 'date'=>'25/02/2025 13:40'],
                        (object)['id'=>8, 'nom'=>'Linda Mogo', 'email'=>'linda.mogo@monmiam.com', 'role'=>'Employé', 'statut'=>'Actif', 'date'=>'20/02/2025 09:10'],
                    ] as $emp)
                        <tr class="border-bottom">
                            <!-- Avatar + Nom -->
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center text-secondary" style="width: 36px; height: 36px; font-weight: 600; font-size: 13px;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $emp->nom }}</span>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3 px-3 text-muted">{{ $emp->email }}</td>

                            <!-- Rôle (Badges couleur Figma) -->
                            <td class="py-3 px-3">
                                @if($emp->role === 'Administrateur')
                                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-medium" style="background-color: #e8d8f8 !important; color: #8e44ad !important;">
                                        Administrateur
                                    </span>
                                @elseif($emp->role === 'Gérant')
                                    <span class="badge bg-info-subtle text-info-emphasis px-3 py-1 rounded-pill fw-medium" style="background-color: #dbeeff !important; color: #2980b9 !important;">
                                        Gérant
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill fw-medium" style="background-color: #f5e8d0 !important; color: #a68a56 !important;">
                                        Employé
                                    </span>
                                @endif
                            </td>

                            <!-- Statut -->
                            <td class="py-3 px-3">
                                @if(strtolower($emp->statut) === 'actif')
                                    <span class="badge rounded-pill bg-success-subtle text-success px-2 py-1" style="font-weight: 500;">
                                        ● Actif
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-2 py-1" style="font-weight: 500;">
                                        ● Inactif
                                    </span>
                                @endif
                            </td>

                            <!-- Date d'ajout -->
                            <td class="py-3 px-3 text-muted" style="font-size: 12px;">{{ $emp->date }}</td>

                            <!-- Actions : Supprimer + Modifier -->
                            <td class="py-3 px-4 text-center">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <form method="POST" action="{{ route('admin.employees.destroy', $emp->id ?? 1) }}" onsubmit="return confirm('Supprimer cet employé ?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-muted px-2" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.employees.edit', $emp->id ?? 1) }}" class="btn btn-sm text-white px-3" style="background-color: #a68a56; border-radius: 4px; font-size: 12px;">
                                        Modifier
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun employé trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer de table -->
        <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center py-3 px-4" style="font-size: 12px; color: #777;">
            <div>
                Affichage de 1 à 8 sur 8 employés
            </div>
            <div>
                <ul class="pagination pagination-sm m-0">
                    <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link border-0" style="background-color: #a68a56; color: #fff;" href="#">1</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bouton secondaire en bas de page (tel que sur Figma) -->
    <div>
        <a href="{{ route('admin.employees.create') ?? '#' }}" class="btn text-white px-3 py-2 fw-medium shadow-sm" style="background-color: #a68a56; border: none; border-radius: 6px; font-size: 13px;">
            + Ajouter un étudiant
        </a>
    </div>

</div>
@endsection