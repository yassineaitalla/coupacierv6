<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use App\Entity\BordereauLivraison;
use Doctrine\ORM\EntityManagerInterface;

class QrController extends AbstractController
{
    #[Route('/qr/all', name: 'qr_all')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les entités BordereauLivraison
        $bordereaux = $entityManager->getRepository(BordereauLivraison::class)->findAll();

        // Initialiser une liste pour stocker les QR Codes
        $qrCodesData = [];

        foreach ($bordereaux as $bordereau) {
            // Générer le QR Code pour chaque bordereau
            $qrCode = new QrCode('http://localhost:8078/qr/all/' . urlencode($bordereau->getReference()));
            $qrCode->setSize(300);
            $qrCode->setEncoding(new Encoding('UTF-8'));

            // Créer un Writer pour le QR Code
            $writer = new PngWriter();
            $imageString = $writer->write($qrCode)->getString();
            $base64 = base64_encode($imageString);

            // Générer le Data URI
            $qrCodeDataUri = 'data:image/png;base64,' . $base64;

            // Ajouter les données du QR Code à la liste
            $qrCodesData[] = [
                'bordereau' => $bordereau,
                'qrCodeDataUri' => $qrCodeDataUri,
                'commandeId' => $bordereau->getIdcommande() ? $bordereau->getIdcommande()->getId() : 'Non attribuée'
            ];
        }

        // Rendre la vue avec la liste des QR Codes
        return $this->render('Qrcode.html.twig', [
            'qrCodesData' => $qrCodesData,
        ]);
    }
}
