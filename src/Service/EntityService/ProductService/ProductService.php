<?php


namespace App\Service\EntityService\ProductService;


use App\Collection\ProductCollection;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Supply;
use App\Model\ProductModel;
use App\Repository\ProductRepository;
use App\Service\EntityService\ProductService\ProductServiceInterface;
use App\Service\FileSystemService\UploadFileInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductService implements ProductServiceInterface
{
    private const PRODUCT_IMAGE_FOLDER = 'products/';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UploadFileInterface
     */
    private $fileUploader;

    /**
     * @var ProductRepository $productRepository
     */
    private $productRepository;

    public function __construct(EntityManagerInterface $entityManager,
                                UploadFileInterface $fileUploader,
                                ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->productRepository = $productRepository;
    }

    public function saveProduct(ProductModel $model)
    {
        $product = $this->setNewProduct($model);
        $this->entityManager->persist($product);

        $modelPhotos = $model->getPhoto();
        $this->uploadProductPhotos($modelPhotos, $product);

        $supply = $this->setSupply($product);
        $this->entityManager->persist($supply);

        $this->entityManager->flush();

    }

    /**
     * @param UploadedFile|null $file
     * @param string $folder
     * @param null $hash
     * @return string|null
     */
    private function upload(?UploadedFile $file, string $folder, $hash = null): ?string
    {
        return $this->fileUploader->uploadFile($file, $folder, $hash);
    }

    private function uploadProductPhotos(array $photos,Product $product): void
    {

        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $productPhoto = new Photo();
                $newPhoto = $this->upload($photo,self::PRODUCT_IMAGE_FOLDER);
                $productPhoto->setHash($newPhoto)
                    ->setProduct($product);

                $this->entityManager->persist($productPhoto);
                $this->entityManager->flush();
            }
        }
    }

    private function setNewProduct(ProductModel $model): Product
    {
        $product = new Product();

        $product->setName($model->getName())
            ->setUnit($model->getUnit())
            ->setPrice($model->getPrice())
            ->setDescription($model->getDescription())
            ->setSeoDescription($model->getSeoDescription())
            ->setSeoTitle($model->getSeoTitle())
            ->setProductSize($model->getProductSize())
            ->setAmountPerUnit($model->getAmountPerUnit())
            ->setWeightPerUnit($model->getWeightPerUnit())
            ->setCategory($model->getCategory());

        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(),self::PRODUCT_IMAGE_FOLDER);
            $product->setTitlePhoto($newTitlePhoto);
        }

        return $product;
    }

    private function setSupply(Product $product): Supply
    {
        $supply = new Supply();

        $supply->setQuantity(0)
            ->setProduct($product);

        return $supply;
    }

    /**
     * @return ProductCollection
     */
    public function getProducts(): ProductCollection
    {
        return new ProductCollection($this->productRepository->findAll());
    }

    public function updateProduct(Product $product, ProductModel $model): void
    {
        $product->setName($model->getName())
            ->setUnit($model->getUnit())
            ->setPrice($model->getPrice())
            ->setDescription($model->getDescription())
            ->setSeoDescription($model->getSeoDescription())
            ->setSeoTitle($model->getSeoTitle())
            ->setProductSize($model->getProductSize())
            ->setAmountPerUnit($model->getAmountPerUnit())
            ->setWeightPerUnit($model->getWeightPerUnit())
            ->setCategory($model->getCategory());

        if ($model->getTitlePhoto() instanceof UploadedFile) {
            $newTitlePhoto = $this->upload($model->getTitlePhoto(),self::PRODUCT_IMAGE_FOLDER, $product->getTitlePhoto());
            $product->setTitlePhoto($newTitlePhoto);
        }

        $this->entityManager->flush();
    }


}