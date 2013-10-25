#include "Bib.h"

void recupNick3 (char* n1, char* n2, char* n3, char nickname[])
{
    //On récupère les 3 premières lettres
    *n1 = nickname[0];
    *n2 = nickname[1];
    *n3 = nickname[2];
}
