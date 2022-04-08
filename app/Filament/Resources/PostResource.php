<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('post_published'),
                Forms\Components\TextInput::make('post_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('post_slug')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('post_image')
                    ->image()
                    ->preserveFilenames()
                    ->maxSize(2048),
                Forms\Components\BelongsToSelect::make('post_category')
                    ->relationship('category', 'category_title'),
                Forms\Components\RichEditor::make('post_content'),
                Forms\Components\Textarea::make('post_excerpt')
                    ->maxLength(65535),
                Forms\Components\MarkdownEditor::make('post_readmore')
                    ->maxLength(65535),
                Forms\Components\BelongsToSelect::make('post_author')
                    ->relationship('user', 'name'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post_title'),
                Tables\Columns\TextColumn::make('post_author'),
                Tables\Columns\TextColumn::make('post_image'),
                Tables\Columns\TextColumn::make('post_category'),
                Tables\Columns\BooleanColumn::make('post_published'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    /**
     * Publishes the resource
     *
     * @return array
     */
    public static function publishResource()
    {
        //Log::debug("publishing post");
        $nowtime = now();
        return [
            'post_published' => true,
            'published_at' => $nowtime->toDateTimeString(),
        ];
    }

    /**
     * Unpublishes the resource
     *
     * @return array
     */
    public static function unpublishResource()
    {
        //Log::debug("unpublishing post");
        return [
            'post_published' => false,
            'published_at' => null,
        ];
    }
}
