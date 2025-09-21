<?php

namespace App\Modules\Course\Application\UseCases\Document;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Document\DocumentDTO;
use App\Modules\Course\Http\Resources\Document\DocumentResource;
use App\Modules\Course\Application\DTOS\Document\DocumentFilterDTO;
use App\Modules\Course\Http\Resources\Document\FullDocumentResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Document\DocumentRepository;

class DocumentUseCase
{

    protected $documentRepository;



    public function __construct()
    {
        $this->documentRepository = new DocumentRepository();
    }

    public function fetchDocuments(DocumentFilterDTO $documentFilterDTO, $withChild = false): DataStatus
    {
        try {
            $documents = $this->documentRepository->filter($documentFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $withChild ? FullDocumentResource::collection($documents) : DocumentResource::collection($documents)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchDocumentDetails(DocumentFilterDTO $documentFilterDTO): DataStatus
    {
        try {
            $document = $this->documentRepository->getById($documentFilterDTO->document_id);
            return DataSuccess(
                status: true,
                message: 'Course Document fetched successfully',
                data: new DocumentResource($document)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createDocument(DocumentDTO $documentDTO): DataStatus
    {
        try {
            $document = $this->documentRepository->create($documentDTO);

            $document->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Document created successfully',
                data: $document
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateDocument(DocumentDTO $documentDTO): DataStatus
    {
        try {
            $document = $this->documentRepository->update($documentDTO->document_id, $documentDTO);
            return DataSuccess(
                status: true,
                message: 'Course Document updated successfully',
                data: $document
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteDocument(DocumentFilterDTO $documentFilterDTO): DataStatus
    {
        try {
            $document = $this->documentRepository->delete($documentFilterDTO->document_id);
            return DataSuccess(
                status: true,
                message: 'Course Document deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
